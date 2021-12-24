import { LoadTransactionAuthorizationRepository } from '@/data/protocol/load-transaction-authorization-repository'
import { DepositModel } from '@/domain/model/deposit'
import { WithdrawModel } from '@/domain/model/withdraw'

export class TransactionAuthorizationRepository implements LoadTransactionAuthorizationRepository {
  async load (deposit: DepositModel, withdraw: WithdrawModel): Promise<boolean> {
    return new Promise(resolve => resolve(true))
  }
}
