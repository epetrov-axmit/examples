<?php
/**
 * Created by PhpStorm.
 * User: ed
 * Date: 18.04.2015
 * Time: 22:02
 */

namespace MmdAdmin\Game\Controller;

use Application\Traits\EntityManagerAwareController;
use Application\Controller\Helper\RedirectWithMessageTrait;
use Epos\UserCore\Entity\User;
use Epos\UserCore\Service\UserService;
use Interop\Container\ContainerInterface;
use Mmd\Game\Dto\ServerFormTo;
use Mmd\Game\Entity\Server;
use Mmd\Game\Form\ServerEditForm;
use Mmd\Game\Form\ServerFilterForm;
use Mmd\Game\Service\ServerService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * Class ServerController
 *
 * @package MmdAdmin\Game\Controller
 */
class ServerController extends AbstractActionController
{

    use EntityManagerAwareController;
    use RedirectWithMessageTrait;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var ServerService
     */
    protected $serverService;

    /**
     * @var ContainerInterface
     */
    protected $formContainer;

    /**
     * @var User
     */
    protected $user;

    /**
     * ServerController constructor.
     *
     * @param UserService        $userService
     * @param ServerService      $serverService
     * @param ContainerInterface $formContainer
     */
    public function __construct(
        UserService $userService, ServerService $serverService, ContainerInterface $formContainer
    ) {
        $this->userService   = $userService;
        $this->serverService = $serverService;
        $this->formContainer = $formContainer;
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        /** @var ServerFilterForm $filterForm */
        $filterForm = $this->formContainer->get('server.filter.form');
        $queryParam = $filterForm->filterValues($this->params()->fromQuery());

        /** @var Server[] $servers */
        $servers = $this->serverService->bindPage($queryParam);

        $viewModel = new ViewModel(
            [
                'servers' => $servers,
                'filter'  => $filterForm,
            ]
        );

        $this->preparePagination('admin/server', ['action' => 'index'], $queryParam);

        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function createAction()
    {
        /** @var ServerEditForm $form */
        $form       = $this->formContainer->get(ServerEditForm::class);
        $success    = '';
        $this->user = $this->userService->getAuthenticatedUser();

        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                $serverDto = $form->getObject();
                $this->serverService->create($serverDto, $this->user);
                $success = sprintf('Сервер %s добавлен', $serverDto->getName());
            }
        }

        return new ViewModel(['form' => $form, 'success' => $success]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function updateAction()
    {
        if (!$id = $this->params()->fromRoute('id')) {
            return $this->redirectToRoute('admin/server', 'ID is not defined', 'admin-servers-list');
        }

        if (!$server = $this->serverService->find($id)) {
            return $this->redirectToRoute(
                'admin/server', sprintf('Server [%s] does not exist', $id), 'admin-servers-list'
            );
        }

        /** @var ServerEditForm $form */
        $form = $this->formContainer->get(ServerEditForm::class);
        $form->bind(ServerFormTo::fromEntity($server));
        $this->user = $this->userService->getAuthenticatedUser();

        $success = '';
        if ($this->getRequest()->isPost()) {
            $form->setData($this->params()->fromPost());
            if ($form->isValid()) {
                /** @var ServerFormTo $serverDto */
                $serverDto = $form->getObject();
                $this->serverService->update($server, $serverDto, $this->user);

                $success = sprintf('Сервер %s добавлен', $server->getName());
            }
        }

        return new ViewModel(['form' => $form, 'server' => $server, 'success' => $success]);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function removeAction()
    {
        if (!$id = $this->params()->fromRoute('id')) {
            return $this->redirectToRoute('admin/server', 'ID is not defined', 'admin-servers-list');
        }

        if (!$server = $this->serverService->find($id)) {
            return $this->redirectToRoute(
                'admin/server', sprintf('Server [%s] does not exist', $id), 'admin-servers-list'
            );
        }

        /** @var ServerFormTo $serverDto */
        $this->serverService->remove($server);
        $success = sprintf('Сервер %s удален', $server->getName());

        return $this->redirectToRoute('admin/server', $success, 'admin-servers-list');
    }

    /**
     * @param string $repository
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository($repository = 'Mmd\\Game\\Entity\\Server')
    {
        return $this->getEntityManager()->getRepository($repository);
    }

    /**
     * @param MvcEvent $e
     *
     * @return mixed|\Zend\Http\Response
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->user = $this->userService->getAuthenticatedUser();

        if (empty($this->user)) {
            return $this->redirectToRoute('dashboard');
        }

        return parent::onDispatch($e);
    }

}
