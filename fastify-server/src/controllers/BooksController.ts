import TABLE_NAMES from '@/knex/constants/tableNames'
import type {FastifyReply, FastifyRequest} from 'fastify'
import knexInstance from '@/knex/knexfile'

export class BooksController {
	public static async search(req: FastifyRequest, res: FastifyReply) {
		res.send(await knexInstance(TABLE_NAMES.books).select())
	}
}
