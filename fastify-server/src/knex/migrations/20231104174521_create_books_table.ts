import { Knex } from 'knex'
import TABLE_NAMES from '@/knex/constants/tableNames'

const tableName = TABLE_NAMES.books
export async function up(knex: Knex): Promise<void> {
	return knex.schema.createTable(tableName, function (table) {
		table
			.integer('id')
			.unique()
			.primary()
			.notNullable()
		table
			.timestamps()
		table
			.string('title')
			.notNullable()
		table.string('year')
		table.string('city')
		table.string('volume')
		table.string('annotation')
		table.string('isbn')
		table.string('pages')

		table.string('filename')
		table.string('hash_sum')

		table.double('price')

		table
			.integer('owner_id')
			.unsigned()
			.references('id')
			.inTable(TABLE_NAMES.users)

	})
}


export async function down(knex: Knex): Promise<void> {
	return knex.schema.dropTableIfExists(tableName)
}

