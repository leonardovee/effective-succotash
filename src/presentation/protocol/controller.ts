import { HttpRequest, HttpResponse } from '@/presentation/protocol/http'

export interface Controller {
  handle: (httpRequest: HttpRequest) => Promise<HttpResponse>
}