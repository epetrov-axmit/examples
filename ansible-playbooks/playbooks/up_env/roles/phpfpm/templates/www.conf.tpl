[www]

user = www-data
group = www-data

listen = /run/php/php7.0-fpm.sock

listen.owner = www-data
listen.group = www-data
listen.mode = 0660

pm = dynamic
pm.max_children = 10
pm.start_servers = 4
pm.min_spare_servers = 2
pm.max_spare_servers = 6
pm.max_requests = 400
pm.status_path = /status

;@fixme - php-fpm does not recognize options below
;emergency_restart_threshold = 10
;emergency_restart_interval = 1m
;process_control_timeout = 10s

ping.path = /ping
ping.response = pong

access.log = {{ php.fpm.logfile }}
access.format = "%R - %u %t \"%m %r%Q%q\" %s %f %{mili}d %{kilo}M %C%%"
slowlog = {{ php.fpm.slowlog }}
;request_slowlog_timeout = 0
;request_terminate_timeout = 0
;rlimit_files = 1024
;rlimit_core = 0
