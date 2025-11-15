In your normal terminal, do the **ifconfig** command to check the IP address associated with the OVPN server you have connected to the hackviser platform.


The php file is ready to use, 
You just need to upload it to the server using ftp, then before triggering it on the server, make sure to have netcat listening on port 4444.

After triggering it on the server check on the listener and you shall have your reverse shell.
