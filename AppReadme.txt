Installed a security certificate from http://curl.haxx.se/ca/cacert.pem
add in the end of php.ini file :
curl.cainfo = "D:\dev\Web\server\Xamp(1822)\cacert.pem"
Resolution : https://github.com/Payum/PayumBundle/issues/99

