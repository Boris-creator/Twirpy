import fastify from 'fastify'
import fastifyEnv from '@fastify/env'
import cors from '@fastify/cors'
import fileUpload from 'fastify-file-upload'
import {options} from '@/env'
import registerRoutes from '@/routes/api'
import knexInstance from '@/knex/instance'
import {serializerCompiler, validatorCompiler} from 'fastify-type-provider-zod'

const server = fastify();

(async function () {


	server.setValidatorCompiler(validatorCompiler)
		.setSerializerCompiler(serializerCompiler)
	await server
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
		.register(fileUpload)
		.register(registerRoutes, {prefix: 'api'})
		.ready()

	server.listen({port: server['config'].PORT}, (err, address) => {
		if (err) {
			console.error(err)
			process.exit(1)
		}
		console.log(`Server listening at ${address}`)
	})

	knexInstance.migrate.latest().catch(console.log)

})()


