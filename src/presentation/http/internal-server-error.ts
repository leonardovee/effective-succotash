import { HttpResponse } from '@/presentation/protocol/http'

export const internalServerError = (error: Error): HttpResponse => ({
  statusCode: 500,
  body: {
    message: error.message
  }
})
