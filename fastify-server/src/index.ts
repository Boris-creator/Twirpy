import fastify from 'fastify'
import fastifyEnv from '@fastify/env'
import {options, ServerWithEnv} from "@/env";

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
})


