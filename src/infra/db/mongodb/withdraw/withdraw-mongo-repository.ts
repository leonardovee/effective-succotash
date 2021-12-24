import { LoadWithdrawsByUserRepository } from '@/data/protocol/load-withdraws-by-user-repository'
import { WithdrawModel } from '@/domain/model/withdraw'
import { MongoHelper } from '@/infra/db/mongodb/helper/mongo-helper'

export class WithdrawMongoRepository implements LoadWithdrawsByUserRepository {
  async loadByUser (id: string): Promise<WithdrawModel[]> {
    const withdrawCollection = await MongoHelper.getCollection('withdraws')
    const withdraws: WithdrawModel[] = MongoHelper.mapList(await withdrawCollection.find({ user: id }).toArray())
    return withdraws
  }
}
