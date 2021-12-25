import { MongoHelper } from '@/infra/db/mongodb/helper/mongo-helper'
import { Collection } from 'mongodb'
import { TransactionMongoRepository } from '@/infra/db/mongodb/transaction/transaction-mongo-repository'

let transactionsCollection: Collection

const makeSut = (): TransactionMongoRepository => new TransactionMongoRepository()

describe('TransactionMongoRepository', () => {
  beforeAll(async () => {
    await MongoHelper.connect(process.env.MONGO_URL ?? '')
  })

  afterAll(async () => {
    await MongoHelper.disconnect()
  })

  beforeEach(async () => {
    transactionsCollection = await MongoHelper.getCollection('transactions')
    await transactionsCollection.deleteMany({})
  })

  describe('createByDepositAndWithdraw()', () => {
    test('Should return the created transaction on success', async () => {
      const sut = makeSut()

      const transaction = await sut.createByDepositAndWithdraw(
        { user: 'any_id', amount: 1000 }, { user: 'other_id', amount: 1000 }
      )

      expect(transaction.deposit.user).toBe('any_id')
      expect(transaction.deposit.amount).toBe(1000)

      expect(transaction.withdraw.user).toBe('other_id')
      expect(transaction.withdraw.amount).toBe(1000)
    })

    test('Should create the transaction', async () => {
      const sut = makeSut()

      await sut.createByDepositAndWithdraw(
        { user: 'any_id', amount: 1000 }, { user: 'other_id', amount: 1000 }
      )

      const transaction = await transactionsCollection.findOne({ 'deposit.user': 'any_id' })

      expect(transaction.deposit.user).toBe('any_id')
      expect(transaction.deposit.amount).toBe(1000)

      expect(transaction.withdraw.user).toBe('other_id')
      expect(transaction.withdraw.amount).toBe(1000)
    })
  })
})
