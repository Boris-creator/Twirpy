import type { Knex } from 'knex'
import dotenv from 'dotenv'
import path from 'path'

dotenv.config({path: path.resolve(__dirname, '../../.env')})

const config: { [key: string]: Knex.Config } = {
	development: {
		client: 'mysql',
		connection: {
			host : '127.0.0.1',
			port : 3306,
			user : process.env.DB_USER,
			password : process.env.DB_PASS,
			database : process.env.DB_NAME
		},
		migrations: {
			tableName: 'knex_migrations',
			directory: path.join(__dirname, './migrations')
		}
	},

	staging: {
		client: 'postgresql',
		connection: {
			database: 'my_db',
			user: 'username',
			password: 'password'
		},
		pool: {
			min: 2,
			max: 10
		},
		migrations: {
			tableName: 'knex_migrations'
		}
	},

}

export default config
