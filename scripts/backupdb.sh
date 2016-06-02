#!/bin/sh
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
DBDIR=$DIR/../db-bak
if [ ! -d "$DBDIR" ]; then
    mkdir $DBDIR
fi
mysqldump -ubookborrow_user  -pbookborrow_pass bookborrow_db > $DBDIR/bookborrow_db-$(date +%Y-%m-%d-%H.%M.%S).sql