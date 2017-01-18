<?php

namespace MmdAdmin\Game\Controller;

use Application\Controller\Helper\RedirectWithMessageTrait;
use Application\Traits\EntityManagerAwareController;
use Epos\UserCore\Entity\User;
use Epos\UserCore\Service\UserService;
use Interop\Container\ContainerInterface;
use Mmd\Game\Dto\GameFormTo;
use Mmd\Game\Entity\Game;
use Mmd\Game\Form\GameEditForm;
use Mmd\Game\Form\GameFilterForm;
use Mmd\Game\Service\GameService;
use Mmd\Game\Service\GameTransformer;
use MmdAdmin\Game\Statistics\Form\GameStatisticsFilterForm;
use MmdAdmin\Game\Statistics\Service\GameStatisticsService;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * Class GameController
 *
 * @package MmdAdmin\Game\Controller
 */
class GameController extends AbstractActionController
{

    use EntityManagerAwareController;
    use RedirectWithMessageTrait;

    /**
     * @var GameService $gameService
     */
    protected $gameService;

    /**
     * @var UserService $userService
     */
    protected $userService;

    /**
     * @var GameStatisticsService $gameStatisticsService
     */
    protected $gameStatisticsService;

    /**
     * @var ContainerInterface $formService
     */
    protected $formContainer;

    /**
     * @var User
     */
    protected $user;

    /**
     * GameController constructor.
     *
     * @param GameService           $gameService
     * @param UserService           $userService
     * @param GameStatisticsService $gameStatisticsService
     * @param ContainerInterface    $formContainer
     */
    public function __construct(
        GameService $gameService,
        UserService $userService,
        GameStatisticsService $gameStatisticsService,
        ContainerInterface $formContainer
    ) {
        $this->gameService           = $gameService;
        $this->userService           = $userService;
        $this->gameStatisticsService = $gameStatisticsService;
        $this->formContainer         = $formContainer;
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        /** @var GameFilterForm $filterForm */
        $filterForm  = $this->formContainer->get('game.filter.form');
        $queryParams = $filterForm->filterValues($this->params()->fromQuery());

        /** @var Game[] $games */
        $games = $this->gameService->listPaginated($queryParams);

        $viewModel = new ViewModel(
            [
                'games'  => $games,
                'filter' => $filterForm,
            ]
        );

        $this->preparePagination('admin/game', ['action' => 'index'], $queryParams);

        return $viewModel;
    }

    /**
     * @return array|bool|\Zend\Http\Response|ViewModel
     */
    public function createAction()
    {
        /** @var GameEditForm $form */
        $form = $this->formContainer->get('game.edit.form');
        $form->setBindOnValidate(false);

        $success = '';

        $prg = $this->fileprg($form);

        if ($prg instanceof Response) {
            return $prg;
        } elseif (is_array($prg)) {
            if ($form->isValid()) {
                $form->bindValues();
                /** @var GameFormTo $gameDto */
                $gameDto = $form->getObject();
                $this->gameService->create($gameDto, $this->user);
                $success = sprintf('Игра %s добавлена', $gameDto->getName());
            }
        }

        return new ViewModel(['form' => $form, 'success' => $success]);
    }

    /**
     * @return array|bool|\Zend\Http\Response|ViewModel
     */
    public function updateAction()
    {
        $success = '';
        if (!$id = $this->params()->fromRoute('id')) {
            return $this->redirectToRoute('admin/game', 'ID is not defined', 'admin-games-list');
        }

        if (!$game = $this->gameService->find($id)) {
            return $this->redirectToRoute('admin/game', sprintf('Game [%s] does not exist', $id), 'admin-games-list');
        }

        /** @var GameEditForm $form */
        $form = $this->formContainer->get('game.edit.form');
        $form->setBindOnValidate(false);

        $gameTransformer = new GameTransformer();

        $form->bind($gameTransformer->toGameFormTo($game));

        $prg = $this->fileprg($form);

        if ($prg instanceof Response) {
            return $prg;
        } elseif (is_array($prg)) {
            if ($form->isValid()) {
                $form->bindValues();
                /** @var GameFormTo $gameDto */
                $gameDto = $form->getObject();
                $this->gameService->update($game, $gameDto, $this->user);
                $success = sprintf('Игра %s отредактирована', $gameDto->getName());
            }
        }

        return new ViewModel(['game' => $game, 'form' => $form, 'success' => $success]);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function removeAction()
    {
        if (!$id = $this->params()->fromRoute('id')) {
            return $this->redirectToRoute('admin/game', 'ID is not defined', 'admin-games-list');
        }

        $game = $this->gameService->find($id);
        if (!$game) {
            return $this->redirect()->toRoute('admin/game');
        }

        $this->gameService->remove($game);

        return $this->redirectToRoute('admin/game', sprintf('Игра %s удалена', $game->getName()), 'admin-games-list');
    }

    /**
     * @return ViewModel
     */
    public function statisticsAction()
    {
        /** @var GameStatisticsFilterForm $filterForm */
        $filterForm  = $this->formContainer->get(GameStatisticsFilterForm::class);
        $queryParams = $filterForm->filterValues($this->params()->fromQuery());
        $paginator   = $this->gameStatisticsService->findGamesPopulation($queryParams);

        $viewModel = new ViewModel(
            [
                'paginator' => $paginator,
                'filter'    => $filterForm,
            ]
        );

        $this->preparePagination('admin/game', ['action' => 'statistics'], $queryParams);

        return $viewModel;
    }

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->user = $this->userService->getAuthenticatedUser();

        return parent::onDispatch($e);
    }
}
