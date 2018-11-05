#!/bin/bash
envsubst < /backup.cron | tee /etc/cron.d/backup.cron
printenv | grep BK | grep -v CRON | sed 's/^\(.*\)$/export \1/g' > /root/project_env.sh 
printenv | grep DB | sed 's/^\(.*\)$/export \1/g' >> /root/project_env.sh
printenv | grep AWS | sed 's/^\(.*\)$/export \1/g' >> /root/project_env.sh
chmod 700 /etc/cron.d/backup.cron 
chmod 700 /root/project_env.sh 
cat /etc/cron.d/*.cron | crontab - 
cron -f 
tail -f /var/log/cron.log
