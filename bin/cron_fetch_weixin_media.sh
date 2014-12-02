#!/bin/bash
echo 'fetching outsite media ...'
STATUS=ok
# STATUS=$(curl 'http://www.zhangge.me/labs/fetch/media/weixin')

while [[ $STATUS != "done" ]]
do
	STATUS=$(curl 'http://www.zhangge.me/labs/fetch/media/weixin')
done

echo DONE
