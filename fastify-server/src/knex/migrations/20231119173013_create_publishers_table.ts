import { Knex } from 'knex'
import TABLE_NAMES from '@/knex/constants/tableNames'

const tableName = TABLE_NAMES.publishers

export async function up(knex: Knex): Promise<void> {
	return knex.schema.createTable(tableName, function (table) {
		table
			.increments('id')
			.unique()
		table
			.timestamps()
		table.string('name')
	})
}


export async function down(knex: Knex): Promise<void> {
	return knex.schema.dropTableIfExists(tableName)
}

