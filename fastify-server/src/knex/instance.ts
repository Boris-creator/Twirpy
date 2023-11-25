import knex from 'knex'
import knexConfig from '@/knex/knexfile'

const knexInstance = knex(knexConfig)
export default knexInstance
