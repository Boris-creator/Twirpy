import {Knex} from 'knex'
import TABLE_NAMES from '@/knex/constants/tableNames'

const tableName = TABLE_NAMES.users
export async function up(knex: Knex): Promise<void> {
	return knex.schema
		.createTable(tableName, function (table) {
			table
				.integer('id')
				.unsigned()
				.unique()
				.primary()
				.notNullable()
			table
				.string('email')
				.unique()
				.notNullable()
			table
				.string('name')
				.unique()
				.notNullable()
			table.string('password').notNullable()
			table.double('balance').defaultTo(30 * 10 ** 3)
			table
				.timestamps(true, true)
		})
}


export async function down(knex: Knex): Promise<void> {
	return knex.schema.dropTableIfExists(tableName)
}

