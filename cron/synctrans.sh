#!/bin/sh
url="http://backoffice.marimakan.co.id/moka/transactions"
since=$(date +"%Y-%m-%d");
until=$(date +"%Y-%m-%d" -d "+ 1 days");
curl -XPOST  -H 'Content-Length: 0' -d '{
	"since":${since},
	"until":${until}
}' ${url}