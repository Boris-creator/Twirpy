const schema = {
	type: 'object',
	required: ['PORT'],
	properties: {
		PORT: {
			type: 'number',
			default: 8000
		},
		NODE_ENV: {
			type: 'string',
			default: 'development'
		}
	}
}
const configKey = 'config'
export const options = {schema, configKey, dotenv: true}

declare module 'fastify' {
	interface FastifyInstance {
		[configKey]: { PORT: number, NODE_ENV: 'development' | 'production' }
	}
}

