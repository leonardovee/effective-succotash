export class UnauthorizedTransactionError extends Error {
  constructor () {
    super(`Bussiness shoudn't pay users.`)
    this.name = 'UnauthorizedTransactionError';
  }
}