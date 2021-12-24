import { HttpResponse } from '@/presentation/protocol/http'

export const unprocessableEntity = (error: Error): HttpResponse => ({
  statusCode: 422,
  body: {
    message: error.message
  }
})
