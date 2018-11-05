#!/bin/bash
## ----------------------------------------------------
# Install AWScli: pip install awscli
# Add to PATH: ~/.local/bin
# Run: aws configure
# Config PATH: ~/.aws/
# Edit params
# Config Cron Job
## ----------------------------------------------------
# Params:
source /root/project_env.sh

export AWS_ACCESS_KEY_ID=${AWS_KEY}
export AWS_SECRET_ACCESS_KEY=${AWS_SECRET}
export AWS_DEFAULT_REGION=${AWS_REGION}
export AWS_DEFAULT_OUTPUT=json

dest="/tmp"
#-------------------------------------------------------

#-------------------------------------------------------
day=$(date +%Y-%m-%d)
db_file="${BK_NAME}.$day.sql"
archive_file="${BK_NAME}.bk.$day.tar.xz"
#-------------------------------------------------------
echo "Backup database"
DB_BK=""
if [[ $DB_DRIVER == "mysql" ]]; then
  mysqldump -u $DB_USERNAME -p$DB_PASSWORD -h $DB_HOST --all-databases > $dest/$db_file
  DB_BK="TRUE"
else
  if [[ $DB_DRIVER == "postgres" ]]; then
    export PGPASSWORD="$DB_PASSWORD"
    pg_dumpall -U $DB_USERNAME -h $DB_HOST --clean --file=$dest/$db_file
    DB_BK="TRUE"
  else
    if [[ $DB_DUMPCMD != "" ]]; then
      $DB_DUMPCMD
      DB_BK="TRUE"
    fi
  fi
fi
#-------------------------------------------------------
echo "Generate backup file"
if [[ DB_BK != "" ]]; then
	tar -czv ${BK_FILES} $dest/$db_file > $dest/$archive_file
else  
	tar -czv ${BK_FILES} > $dest/$archive_file
fi
#-------------------------------------------------------
echo "Check S3 bucket"
bucket=$(aws s3 ls | grep "$BK_NAME" | cut -d" " -f3)
if [ ! $bucket ]; then
  aws s3 mb s3://$BK_NAME 
fi
#-------------------------------------------------------
echo "Copy file to S3"
aws s3 cp $dest/$archive_file s3://$BK_NAME/ 
#-------------------------------------------------------
echo "Rotate files in S3"
n=$(aws s3 ls s3://$BK_NAME | grep $BK_NAME | wc -l)
if [ $n -gt $BK_FRAME ]; then
  del=$(aws s3 ls s3://$BK_NAME/ | grep $BK_NAME | sed 's/  */ /g' | cut -d" " -f4 | head -n1)
  echo "Eliminado s3://$BK_NAME/$del"
  aws s3 rm s3://$BK_NAME/$del
fi
#-------------------------------------------------------
echo "Remove local backup files"
rm $dest/$db_file
rm $dest/$archive_file
#-------------------------------------------------------
