import TABLE_NAMES from '@/knex/constants/tableNames'
import type {FastifyReply, FastifyRequest} from 'fastify'
import knexInstance from '@/knex/instance'

export class PublishersController {
	public static async search(req: FastifyRequest, res: FastifyReply) {
		res.send(await knexInstance(TABLE_NAMES.publishers).select())
	}
}
