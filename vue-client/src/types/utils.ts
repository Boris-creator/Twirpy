export type Nullable<T> = T | null
export type NullableFields<T extends Object> = { [Key in keyof T]: Nullable<T[Key]> }
