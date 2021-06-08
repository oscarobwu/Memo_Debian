#!/bin/sh

### BEGIN INIT INFO
# Provides:          prometheus
# Required-Start:    $remote_fs
# Required-Stop:     $remote_fs
# Should-Start:      $all
# Should-Stop:       $all
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: monitoring system and time series database.
# Description:       Prometheus is a systems and service monitoring system.
#                    It collects metrics from configured targets at given intervals,
#                    evaluates rule expressions, displays the results,
#                    and can trigger alerts if some condition is observed to be true.
### END INIT INFO

set -e

. /lib/lsb/init-functions


NAME=prometheus
DESC="Prometheus monitoring system"
DAEMON=/usr/local/bin/prometheus
USER=prometheus
CONFIGDIR=/etc/prometheus
DATADIR=/var/lib/prometheus/data
PID="/var/run/prometheus/$NAME.pid"
LOG="/var/log/prometheus/$NAME.log"
GOSU=/usr/sbin/gosu

ALERTMANAGER_OPTS=

DAEMON_OPTS="$ALERTMANAGER_OPTS"
DAEMON_OPTS="$DAEMON_OPTS --config.file=$CONFIGDIR/prometheus.yml --storage.tsdb.path=$DATADIR"
DAEMON_OPTS="$DAEMON_OPTS --web.console.templates=$CONFIGDIR/consoles --web.console.libraries=$CONFIGDIR/console_libraries"

# Check if DAEMON binary exist
[ -f $DAEMON ] || exit 0

[ -f "/etc/default/$NAME" ] && . /etc/default/$NAME


service_not_configured () {
  if [ "$1" != "stop" ]; then
    printf "\tPlease configure $NAME and then edit /etc/default/$NAME\n"
    printf "\tand set the \"START\" variable to \"yes\" in order to allow\n"
    printf "\t$NAME to start.\n"
  fi
  exit 0
}

service_checks () {
  # Check if START variable is set to "yes", if not we exit.
  if [ "$START" != "yes" ]; then
    service_not_configured $1
  fi

  # Prepare directories
  mkdir -p "/var/run/prometheus" "/var/log/prometheus"
  chown -R $USER "/var/run/prometheus" "/var/log/prometheus"

  # Check if PID exists
  if [ -f "$PID" ]; then
    PID_NUMBER=`cat $PID`
    if [ -z "`ps axf | grep ${PID_NUMBER} | grep -v grep`" ]; then
      echo "Service was aborted abnormally; clean the PID file and continue..."
      rm -f "$PID"
    else
      echo "Service already started; skip..."
      exit 1
    fi
  fi
}

start () {
  service_checks $1
  $GOSU $USER   $DAEMON $DAEMON_OPTS  > $LOG 2>&1  &
  RETVAL=$?
  echo $! > $PID

  if [ $RETVAL ]; then
    log_end_msg 0
  else
    log_end_msg 1
  fi
}

stop () {
  if start-stop-daemon --retry TERM/5/KILL/5 --oknodo --stop --quiet --pidfile $PID 2>&1 1>$LOG
  then
    log_end_msg 0
    rm $PID
  else
    log_end_msg 1
  fi
}


case "$1" in
  start)
    log_daemon_msg "Starting $DESC -" "$NAME"
    start
    ;;

  stop)
    log_daemon_msg "Stopping $DESC -" "$NAME"
    stop
    ;;

  reload)
    log_daemon_msg "Reloading $DESC configuration -" "$NAME"
    if start-stop-daemon --stop --signal HUP --quiet --oknodo --pidfile $PID --startas /bin/bash -- -c "exec $DAEMON $DAEMON_OPTS > $LOG 2>&1"
    then
      log_end_msg 0
    else
      log_end_msg 1
      fi
    ;;

  restart|force-reload)
    log_daemon_msg "Restarting $DESC -" "$NAME"
    stop
    start
    ;;

  syntax)
    $DAEMON --help
    ;;

  status)
    status_of_proc -p $PID $DAEMON $NAME
    ;;

  *)
    log_action_msg "Usage: /etc/init.d/$NAME {start|stop|reload|restart|force-reload|syntax|status}"
    ;;
esac

exit 0
