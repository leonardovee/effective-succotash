import { LoadDepositsByUserRepository } from '@/data/protocol/load-deposits-by-user-repository'
import { DepositModel } from '@/domain/model/deposit'
import { MongoHelper } from '@/infra/db/mongodb/helper/mongo-helper'

export class DepositMongoRepository implements LoadDepositsByUserRepository {
  async loadByUser (id: string): Promise<DepositModel[]> {
    const depositCollection = await MongoHelper.getCollection('deposits')
    const deposits: DepositModel[] = MongoHelper.mapList(await depositCollection.find({ user: id }).toArray())
    return deposits
  }
}
