#!/bin/bash
datestamp=20140314
LINES=25000
PREPEND='email,_website,_store,confirmation,created_at,created_in,disable_auto_group_change,dob,firstname,gender,group_id,lastname,middlename,password_hash,prefix,reward_update_notification,reward_warning_notification,rp_token,store_id,suffix,taxvat,website_id,password,_address_city,_address_company,_address_country_id,_address_fax,_address_firstname,_address_lastname,_address_middlename,_address_postcode,_address_prefix,_address_region,_address_street,_address_suffix,_address_telephone,_address_vat_id,_address_default_billing_,_address_default_shipping_,customerid,email_pref,audio_pref,video_pref,web_user_id
"Expected Rows",25000,admin,,NULL,Admin,0,,,,1,,,,,1,1,,0,,,1,,,,,,,,,,,,,,,,0,0,0,,,,'

#echo "$PREPEND"

#PREPEND=`head -2 customer$datestamp-unix.csv`
split -l $LINES customer$datestamp-unix.csv customer$datestamp.csv-

# mv customer$datestamp.csv-aa customer$datestamp-aa.csv
for x in `ls customer$datestamp.csv-*`
do
	#echo $x
	tag=${x: -2}
	newfile='customer'$datestamp'-'$tag'.csv'
	echo $newfile
	if [ $tag = "aa" ]; then
		#echo "Skipping aa"
		mv $x $newfile
		continue # skip first file because it has the header already
	fi
	echo $newfile
	echo "$PREPEND" > $newfile
  	cat $x >> $newfile
	sed -i '/^$/d' $newfile # remove blank lines from newfile
	rm $x
done
# mv customer$datestamp.csv-ab customer$datestamp-ab.csv
# mv customer$datestamp.csv-ac customer$datestamp-ac.csv
# mv customer$datestamp.csv-ad customer$datestamp-ad.csv
# mv customer$datestamp.csv-ae customer$datestamp-ae.csv
# mv customer$datestamp.csv-af customer$datestamp-af.csv
# mv customer$datestamp.csv-ag customer$datestamp-ag.csv
# mv customer$datestamp.csv-ah customer$datestamp-ah.csv
# mv customer$datestamp.csv-ai customer$datestamp-ai.csv
# mv customer$datestamp.csv-aj customer$datestamp-aj.csv
# mv customer$datestamp.csv-ak customer$datestamp-ak.csv
# mv customer$datestamp.csv-al customer$datestamp-al.csv
# mv customer$datestamp.csv-am customer$datestamp-am.csv
# mv customer$datestamp.csv-an customer$datestamp-an.csv
# mv customer$datestamp.csv-ao customer$datestamp-ao.csv
# mv customer$datestamp.csv-ap customer$datestamp-ap.csv
# mv customer$datestamp.csv-aq customer$datestamp-aq.csv
# mv customer$datestamp.csv-ar customer$datestamp-ar.csv
# mv customer$datestamp.csv-as customer$datestamp-as.csv
# mv customer$datestamp.csv-at customer$datestamp-at.csv
# mv customer$datestamp.csv-au customer$datestamp-au.csv
# mv customer$datestamp.csv-av customer$datestamp-av.csv
# mv customer$datestamp.csv-aw customer$datestamp-aw.csv
# mv customer$datestamp.csv-ax customer$datestamp-ax.csv
# mv customer$datestamp.csv-ay customer$datestamp-ay.csv
# mv customer$datestamp.csv-az customer$datestamp-az.csv
# mv customer$datestamp.csv-ba customer$datestamp-ba.csv
# mv customer$datestamp.csv-bb customer$datestamp-bb.csv
# mv customer$datestamp.csv-bc customer$datestamp-bc.csv
# mv customer$datestamp.csv-bd customer$datestamp-bd.csv
# mv customer$datestamp.csv-be customer$datestamp-be.csv
# mv customer$datestamp.csv-bf customer$datestamp-bf.csv
# mv customer$datestamp.csv-bg customer$datestamp-bg.csv
# mv customer$datestamp.csv-bh customer$datestamp-bh.csv
# mv customer$datestamp.csv-bi customer$datestamp-bi.csv
# mv customer$datestamp.csv-bj customer$datestamp-bj.csv
# mv customer$datestamp.csv-bk customer$datestamp-bk.csv
# mv customer$datestamp.csv-bl customer$datestamp-bl.csv
# mv customer$datestamp.csv-bm customer$datestamp-bm.csv
# mv customer$datestamp.csv-bn customer$datestamp-bn.csv
# mv customer$datestamp.csv-bo customer$datestamp-bo.csv
# mv customer$datestamp.csv-bp customer$datestamp-bp.csv
# mv customer$datestamp.csv-bq customer$datestamp-bq.csv
# mv customer$datestamp.csv-br customer$datestamp-br.csv
# mv customer$datestamp.csv-bs customer$datestamp-bs.csv
# mv customer$datestamp.csv-bt customer$datestamp-bt.csv
# mv customer$datestamp.csv-bu customer$datestamp-bu.csv
# mv customer$datestamp.csv-bv customer$datestamp-bv.csv
# mv customer$datestamp.csv-bw customer$datestamp-bw.csv
# mv customer$datestamp.csv-bx customer$datestamp-bx.csv
# mv customer$datestamp.csv-by customer$datestamp-by.csv
# mv customer$datestamp.csv-bz customer$datestamp-bz.csv
# mv customer$datestamp.csv-ca customer$datestamp-ca.csv
# mv customer$datestamp.csv-cb customer$datestamp-cb.csv
# mv customer$datestamp.csv-cc customer$datestamp-cc.csv
# mv customer$datestamp.csv-cd customer$datestamp-cd.csv
# mv customer$datestamp.csv-ce customer$datestamp-ce.csv
# mv customer$datestamp.csv-cf customer$datestamp-cf.csv
# mv customer$datestamp.csv-cg customer$datestamp-cg.csv
