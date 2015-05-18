#!/bin/bash

# A script to run a silex project quickly
# Edit the uppercase words

##########
# Colors #
##########

red='\033[0;31m'
nocolor='\033[0m'
black='\033[0;30m'
blue='\033[0;34m'
green='\033[0;32m'
cyan='\033[0;36m'
purple='\033[0;35m'
orange='\033[0;33m'
lightgray='\033[0;37m'
darkgray='\033[1;30m'
lightblue='\033[1;34m'
lightgreen='\033[1;32m'
lightcyan='\033[1;36m'
lightred='\033[1;31m'
lightpurple='\033[1;35m'
yellow='\033[1;33m'
white='\033[1;37m'

#############
# Variables #
#############

#global
scriptpath="`dirname \"$0\"`"
scriptpath="`( cd \"$scriptpath\" && pwd )`"

# elk
elkvolume="/var/docker/elk/idci"
elkcontainer="idci_elk_1"
kibanajson="$scriptpath/kibana.json"

# The main function
main() {
    echo "---------------------------------------------------"
    addHostEntry "dev.idci.fr"
    addHostEntry "dev.logs.idci.fr"
    initELK
    docker-compose up -d
    echo "---------------------------------------------------"
    printf "Your root directory is at ${cyan}$scriptpath${nocolor}\n"
    printf "You can access your project at ${cyan}http://dev.idci.fr${nocolor}\n"
    printf "You can see your logs at ${cyan}http://dev.logs.idci.fr${nocolor}\n"
}

#############
# Functions #
#############

# Add an host entry
# $1 : domain
addHostEntry () {
    domain=$1;   
    success=0
    filename=/etc/hosts
    hostline="127.0.0.1        $domain"
    # Determine if the line already exists in /etc/hosts
    grep -q "$domain" "$filename"  # -q is for quiet

    # Grep's return error code can then be checked. No error=success
    if ! [ $? -eq $success ]; then
      # If the line wasn't found, add it using an printf append >>
      sudo bash -c "echo '$hostline' >> $filename"
      printf "The entry $domain was added to $filename\n"
    fi
}

# insert visualization and dashboard in elasticsearch cluster
initELK () {
    if ! [ -d "$elkvolume/logstash" ]; then
        printf "Creating temporary elk container...\n"
        createELKContainer > /dev/null 2>&1
        printf " * Inserting Kibana dashboard and visualizations\n"
        insertKibanaData > /dev/null 2>&1
        printf "Removing temporary container\n"
        removeELKContainer > /dev/null 2>&1
    fi
}

# create a temporary elk container
createELKContainer () {
    docker run -d --name elkcontainer -v $elkvolume:/data -v $kibanajson:/kibana.json ovski/elk:elasticdump
    sleep 20 # wait for elasticsearch process to start
}

insertKibanaData () {
    # echo | --> http://stackoverflow.com/questions/6264596/simulating-enter-keypress-in-bash-script
    echo |docker exec -i elkcontainer bash -c "curl -XPUT 'http://localhost:9200/.kibana/'"
    echo |docker exec -i elkcontainer bash -c "elasticdump --input=/kibana.json --output=http://localhost:9200/.kibana --type=data"
}

# remove the temporary mysql container
removeELKContainer() {
    docker stop elkcontainer
    docker rm elkcontainer
}

####################
# Start the script #
####################

main
