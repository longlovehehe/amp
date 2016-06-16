Summary: Agent Management Platform
Name: amp
Version: 3.3.0
Release: 0
Vendor: zed-3, Inc 2003-2015
License: zed-3, Inc 2003-2015
Group: Applications/ASG
BuildRoot: /tmp/BUILD/AMP
%description
This package contains the web files for Agent Management Platform

%pre 

if [ "$1" = "2" ]; then 
    if [ -e /usr/local/amp/private/config/language.ini ]; then 
        /bin/cp /usr/local/amp/private/config/language.ini /tmp/amp_language.ini 
    fi
fi 

%post
if ! [ -d /usr/local/asg/www/html ];
then
    /bin/mkdir -p /usr/local/asg/www/html
fi

if ! [ -L /usr/local/asg/www/html/amp ];
then
    /bin/ln -s /usr/local/amp/www /usr/local/asg/www/html/amp
fi

if ! [ -L /usr/local/amp/runtime ];
then
    /bin/ln -s /var/amp/runtime /usr/local/amp/runtime
fi

if [ -d /usr/local/amp/www/files ];
then
    rm -rf /usr/local/amp/www/files
fi

if ! [ -L /usr/local/amp/www/files ];
then
    /bin/ln -s /var/amp/upload /usr/local/amp/www/files
fi

chown apache.apache -R /usr/local/amp
rm -f /var/amp/runtime/cache/*

if [ -x /etc/init.d/httpd ];
then
    /etc/init.d/httpd restart >/dev/null 2>&1
fi


if [ "$1" = "2" ]; then 
    if [ -e /tmp/amp_language.ini ]; then 
        /bin/mv /tmp/amp_language.ini /usr/local/amp/private/config/language.ini 
    fi
fi 

%postun

if [ "$1" = "0" ]; then
    if [ -L /usr/local/asg/www/html/amp ];
    then
        rm -f /usr/local/asg/www/html/amp
    fi

    if [ -d /usr/local/amp ];
    then
        rm -rf /usr/local/amp
    fi

    if [ -d /var/amp ];
    then
        rm -rf /var/amp
    fi
fi

%files
%defattr (-,root,root)
/usr/local/amp
/var/amp
