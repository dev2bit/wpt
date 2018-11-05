FROM ubuntu:16.04
MAINTAINER info@dev2bit.com
 
# Install
RUN apt-get update && apt-get install -y cron tzdata

RUN apt-get install -y \
  python-pip \
  postgresql-client-common \
  mysql-client \
  gettext-base

RUN pip install awscli

RUN rm /etc/localtime && ln -s /usr/share/zoneinfo/Europe/Madrid /etc/localtime

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

COPY ./backup.cron /backup.cron
RUN chmod 777 /backup.cron

COPY ./start.sh /start.sh
RUN chmod 700 /start.sh

COPY ./backup.sh /root/.local/bin/backup.sh
RUN chmod 777 /root/.local/bin/backup.sh

# Run the command on container startup
CMD /start.sh
