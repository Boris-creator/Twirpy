import fastify from 'fastify'
import fastifyEnv from '@fastify/env'
import {options, ServerWithEnv} from '@/env'
import knex from 'knex'
import config from '@/knex/knexfile'

const server = fastify()

server.get('/ping', async (request, reply) => {
	return 'pong\n'
})

server
	.register(fastifyEnv, options)
	.ready().then(() => {
		const app = server as unknown as ServerWithEnv
		app.listen({port: app['config'].PORT}, (err, address) => {
			if (err) {
				console.error(err)
				process.exit(1)
			}
			console.log(`Server listening at ${address}`)
		})
		const db = knex(config[app['config'].NODE_ENV])
		db.migrate.latest().catch(console.log)
	})


