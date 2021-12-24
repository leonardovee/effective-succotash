export class UnauthorizedTransactionError extends Error {
  constructor (message: string) {
    super(message)
    this.name = 'UnauthorizedTransactionError'
  }
}
