import sys
import time
import json
import XenAPI
import traceback

args = pool_data = {}
session = ''

def _do_main(query):
	global args, session
	#
	#{u'opt': u'get_host_list', 
	# u'pool_data': {u'uuid': u'7106005d-e8e3-4da5-8adc-4e8035da77ad', u'master': u'10.11.253.43', u'user': u'root', u'pass': u'Rjkj@efly#123'}, 
	#u'module': u'ecloud_pool'}
	#
	
	args = json.loads(query)
	pool_data = args['pool_data']
	
	master_url = "http://%s/"%(pool_data['master'])
	session = XenAPI.Session(master_url)
	session.xenapi.login_with_password(pool_data['user'], pool_data['pass'])

	if args['opt'] == 'get_host_list':
		return _get_host_list()

	session.xenapi.session.logout()

def invoke(query):
	try:
		return _do_main(query)
	except:
		traceback.print_exc()

