cat list-en1-semic-3.csv | while read isodata
do

isocode=`echo $isodata | cut -d";" -f2`
isocode=${isocode:0:2}

wget "http://www.crwflags.com/fotw/images/${isocode:0:1}/$isocode.gif" -O images/flags/${isocode}_big1.gif

done
