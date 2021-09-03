# PacketLimiter
A Packet Limiter plugin for PocketMine

A plugin to limit player from sending many packets. Attacker may sent a lot of packet.  
PocketMine does not provide any level of protection against packet spam.  
As such, individual players can send as many packets as they want.  
The solution was to limit the maximum packets/second a client could send.  

## Config
```yml
# Normal player sent ~200 packet every second
packet_per_second: 250
# If reach limit packet per second they will get warning and if reach maximum warning they kicked.
maximum_warning: 5

kick_message: "You sending too many packets!"
```
