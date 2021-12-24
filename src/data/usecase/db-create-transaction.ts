import { DepositModel } from '@/domain/model/deposit'
import { TransactionModel } from '@/domain/model/transaction'
import { WithdrawModel } from '@/domain/model/withdraw'
import { CreateTransaction } from '@/domain/usecase/create-transaction'
import { LoadUserByIdRepository } from '@/data/protocol/load-user-by-id-repository'
import { UnauthorizedTransactionError } from '@/error/unauthorized-transaction-error'
import { LoadWithdrawsByUserRepository } from '@/data/protocol/load-withdraws-by-user-repository'
import { LoadDepositsByUserRepository } from '@/data/protocol/load-deposits-by-user-repository'

export class DbCreateTransaction implements CreateTransaction {
  constructor (
    private readonly loadUserByIdRepository: LoadUserByIdRepository,
    private readonly loadWithdrawsByUserRepository: LoadWithdrawsByUserRepository,
    private readonly loadDepositsByUserRepository: LoadDepositsByUserRepository
  ) {}

  async create (deposit: DepositModel, withdraw: WithdrawModel): Promise<TransactionModel> {
    const payer = await this.loadUserByIdRepository.loadById(withdraw.user)
    if (payer.type === 'bussiness') {
      throw new UnauthorizedTransactionError()
    }
    
    const withdraws = await this.loadWithdrawsByUserRepository.loadByUser(payer.id)
    const withdrawsAmount = withdraws.map((v) => v.amount).reduce((v, a) => v + a, 0)

    const deposits = await this.loadDepositsByUserRepository.loadByUser(deposit.user)
    const depositsAmount = deposits.map((v) => v.amount).reduce((v, a) => v + a, 0)

    const balance = depositsAmount - withdrawsAmount

    if (withdraw.amount > balance) {
      throw new UnauthorizedTransactionError()
    }

    return null
  }
}
