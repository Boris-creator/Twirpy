import fastify from 'fastify'
import fastifyEnv from '@fastify/env'
import cors from '@fastify/cors'
import {options, ServerWithEnv} from '@/env'
import registerRoutes from '@/routes/api'
import knexInstance from '@/knex/knexfile'

const server = fastify()

server
	.register(fastifyEnv, options)
	.register(cors, {
		credentials: true,
		origin: (origin, cb) => {
			if(origin && new URL(origin).hostname === 'localhost'){
				cb(null, true)
				return
			}
			cb(new Error('Not allowed'), false)
		}
	})
	.register(registerRoutes, {prefix: 'api'})
	.ready().then(() => {
		const app = server as unknown as ServerWithEnv
		app.listen({port: app['config'].PORT}, (err, address) => {
			if (err) {
				console.error(err)
				process.exit(1)
			}
			console.log(`Server listening at ${address}`)
		})

		knexInstance.migrate.latest().catch(console.log)
	})


