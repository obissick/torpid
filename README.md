Torpid
--------------
Torpid is a slow query monitor tool used to analyze slow query logs collected from MySQL/MariaDB/Percona instances to identify problematic queries.

### Quickstart ###

If you're just completely itching to start using this tool, here's what you need:

1.	a MySQL database to store query analysis data in.
2.	[pt-query-digest](http://www.percona.com/doc/percona-toolkit/pt-query-digest.html).
	*	You may as well just get the whole [Percona Toolkit](http://www.percona.com/doc/percona-toolkit) while you're at it :)
3.	a slow query log from a MySQL server (see [The Slow Query Log](http://dev.mysql.com/doc/refman/5.5/en/slow-query-log.html) for info on getting one)
4.	a webserver with PHP 5.5+