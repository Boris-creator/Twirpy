import bcrypt from 'bcrypt'
import type { Knex } from 'knex'
import TABLE_NAMES from '@/knex/constants/tableNames'

export async function seed(knex: Knex): Promise<void> {
	await knex(TABLE_NAMES.users).del()

	await knex(TABLE_NAMES.users).insert([
		{
			id: 1,
			name: 'Test',
			email: 'test@gmail.com',
			password: await bcrypt.hash('qwerty', 10)
		},
		{
			id: 2,
			name: 'Test 2',
			email: 'test2@gmail.com',
			password: await bcrypt.hash('qwerty', 10)
		},
	])
}
