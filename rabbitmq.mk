RMQ_CONTAINER=onyxamqp_rabbitmq_1
FRONT_CONTAINER=onyxamqp_frontend_1
RMQ_VHOST=onyx
RMQ_USER=guest

#------------------------------------------------------------------------------
# RabbitMQ configuration
#------------------------------------------------------------------------------
rabbitmqctl = docker exec --tty -i ${RMQ_CONTAINER} rabbitmqctl $1

cli_exec = docker exec --tty -i ${FRONT_CONTAINER} $1

rmq-configure:
	$(call rabbitmqctl, add_vhost ${RMQ_VHOST})
	$(call rabbitmqctl, set_permissions -p ${RMQ_VHOST} ${RMQ_USER} ".*" ".*" ".*")
	$(call cli_exec,vendor/bin/rabbit --host=rabbitmq --port=15672 --password=guest vhost:mapping:create config/built-in/rabbitmq.yml)

rmq-clean-configuration:
	$(call rabbitmqctl, delete_vhost ${RMQ_VHOST})

rmq-reconfigure: rmq-clean-configuration rmq-configure
