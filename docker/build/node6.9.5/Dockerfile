FROM node:6.9.5

RUN apt-get update

RUN apt-get install -y ruby ruby-dev

RUN npm install --global gulp bower
RUN npm install /usr/src/app
RUN bower install --allow-root /usr/src/app
RUN gem install sass

CMD ["gulp", "init", "&&", "gulp", "watch"]