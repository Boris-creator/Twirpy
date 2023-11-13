import type { FastifyPluginCallback} from 'fastify'
import {BooksController} from '@/controllers/BooksController'

const registerRoutes: FastifyPluginCallback = (instance, opts, next) => {
	instance.post('/login', (req, res) => {
		res.send({})
	})
	instance.register((instance, opts, next) => {
		instance.get('/', BooksController.search)
		next()
	}, {prefix: 'books'})
	next()
}

export default registerRoutes
