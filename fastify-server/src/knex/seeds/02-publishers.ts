import type { Knex } from 'knex'
import TABLE_NAMES from '@/knex/constants/tableNames'

export async function seed(knex: Knex): Promise<void> {
	await knex(TABLE_NAMES.publishers).del()

	await knex(TABLE_NAMES.publishers).insert([
		{
			name: 'O\'Reilly'
		},
		{
			name: 'McMillan & Co'
		},
	])
}
