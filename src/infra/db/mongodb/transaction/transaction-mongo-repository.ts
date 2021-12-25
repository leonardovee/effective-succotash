import { CreateTransactionByDepositAndWithdrawRepository } from '@/data/protocol/create-transaction-by-deposit-and-withdraw-repository'
import { DepositModel } from '@/domain/model/deposit'
import { TransactionModel } from '@/domain/model/transaction'
import { WithdrawModel } from '@/domain/model/withdraw'
import { MongoHelper } from '@/infra/db/mongodb/helper/mongo-helper'

export class TransactionMongoRepository implements CreateTransactionByDepositAndWithdrawRepository {
  async createByDepositAndWithdraw (deposit: DepositModel, withdraw: WithdrawModel): Promise<TransactionModel> {
    const transactionsCollection = await MongoHelper.getCollection('transactions')
    const { insertedId } = await transactionsCollection.insertOne({ deposit, withdraw })
    const transaction = await transactionsCollection.findOne({ _id: insertedId })
    return MongoHelper.map(transaction)
  }
}
