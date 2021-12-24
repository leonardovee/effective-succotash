import { DepositModel } from '@/domain/model/deposit'
import { WithdrawModel } from '@/domain/model/withdraw'

export interface LoadTransactionAuthorizationRepository {
  load: (deposit: DepositModel, withdraw: WithdrawModel) => Promise<boolean>
}
