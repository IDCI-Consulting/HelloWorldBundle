FROM ovski/silex-dev

# Add wkhtmltopdf
RUN apt-get update
RUN apt-get install -y xfonts-75dpi fontconfig libxext6 libxrender1 xfonts-base graphicsmagick
RUN wget http://download.gna.org/wkhtmltopdf/0.12/0.12.2.1/wkhtmltox-0.12.2.1_linux-trusty-amd64.deb
RUN dpkg -i wkhtmltox-0.12.2.1_linux-trusty-amd64.deb

# Add dev host entry
RUN mkdir -p     /etc/my_init.d
ADD edit_host.sh /etc/my_init.d/edit_host.sh
RUN chmod +x     /etc/my_init.d/edit_host.sh
