import zod from 'zod'
const bookCreateSchema = zod.object({
	title: zod.string().min(1),
	publisher: zod
		.object({
			id: zod.number().or(zod.null()),
			name: zod.string().min(2)
		})
		.or(zod.null()),
	isbn: zod
		.string()
		.regex(/^\S{10}(\S{3})?$/)
		.or(zod.null()),
})

export default bookCreateSchema

