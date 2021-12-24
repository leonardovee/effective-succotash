import { HttpResponse } from '@/presentation/protocol/http'

export const created = (body: any): HttpResponse => ({
  statusCode: 201,
  body: body
})
