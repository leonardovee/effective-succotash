import { DepositModel } from '@/domain/model/deposit'
import { WithdrawModel } from '@/domain/model/withdraw'
import { TransactionAuthorizationRepository } from '@/infra/web/authorizer/transaction-authorization-repository'

const makeFakeRequest = (): [DepositModel, WithdrawModel] => {
  return [{
    user: 'other_id',
    amount: 1000
  }, {
    user: 'any_id',
    amount: 1000
  }]
}

describe('TransactionAuthorizationRepository', () => {
  describe('load()', () => {
    it('Should return true', async () => {
      const sut = new TransactionAuthorizationRepository()
      const response = await sut.load(...makeFakeRequest())
      expect(response).toBe(true)
    })
  })
})
