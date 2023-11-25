import type {FastifyPluginCallback} from 'fastify'
import {BooksController} from '@/controllers/BooksController'
import {PublishersController} from '@/controllers/PublishersController'
import {type ZodTypeProvider} from 'fastify-type-provider-zod'
import bookCreateRequest from '@/requests/BookCreateRequest'

const registerRoutes: FastifyPluginCallback = (instance, opts, next) => {
	instance.post('/login', (req, res) => {
		res.send({})
	})

	instance.register((instance, opts, next) => {
		instance
			.get('/', BooksController.search)
		instance.withTypeProvider<ZodTypeProvider>().route({
			method: 'post',
			url: '/',
			schema: {
				body: bookCreateRequest
			},
			handler: BooksController.store
		})
		next()
	}, {prefix: 'books'})

	instance.register((instance, opts, next) => {
		instance.get('/', PublishersController.search)
		next()
	}, {prefix: 'publishers'})

	next()
}

export default registerRoutes
