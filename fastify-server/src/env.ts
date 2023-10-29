import {FastifyInstance} from "fastify";

const schema = {
    type: 'object',
    required: ['PORT'],
    properties: {
        PORT: {
            type: 'number',
            default: 8000
        }
    }
}
const configKey = 'config'
export const options = {schema, configKey, dotenv: true}

export type ServerWithEnv = FastifyInstance & Record<typeof configKey, { PORT: number }>
