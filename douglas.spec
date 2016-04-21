%define name douglas
%define install_dir /var/www/phpwebsite/mod/douglas

Summary:   Douglas MacArthur Scholarship Nomination
Name:      %{name}
Version:   %{version}
Release:   %{release}
License:   GPL
Group:     Development/PHP
URL:       http://phpwebsite.appstate.edu
Source:    %{name}-%{version}-%{release}.tar.bz2
Requires:  php >= 5.0.0, php-gd >= 5.0.0, ghostscript
Prefix:    /var/www/phpwebsite
BuildArch: noarch

%description
The Douglas MacArthur Scholarship Nomination System

%prep
%setup -n %{name}-%{version}-%{release}

%post
/usr/bin/curl -L -k http://127.0.0.1/apc/clear

%install
mkdir -p "$RPM_BUILD_ROOT%{install_dir}"
cp -r * "$RPM_BUILD_ROOT%{install_dir}"

# Default Deletes for clean RPM

rm -Rf "$RPM_BUILD_ROOT%{install_dir}/docs/"
rm -Rf "$RPM_BUILD_ROOT%{install_dir}/.hg/"
rm -f "$RPM_BUILD_ROOT%{install_dir}/.hgtags"
rm -f "$RPM_BUILD_ROOT%{install_dir}/build.xml"
rm -f "$RPM_BUILD_ROOT%{install_dir}/*.spec"
rm -f "$RPM_BUILD_ROOT%{install_dir}/phpdox.xml"
rm -f "$RPM_BUILD_ROOT%{install_dir}/cache.properties"

%clean
rm -rf "$RPM_BUILD_ROOT%{install_dir}"

%files
%defattr(-,root,root)
%{install_dir}

%changelog
* Mon Aug  6 2012 Jeff Tickle <jtickle@tux.appstate.edu>
- Initial RPM for Douglas
