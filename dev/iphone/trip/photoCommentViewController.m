//
//  photoCommentViewController.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-24.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "photoCommentViewController.h"

@interface photoCommentViewController ()

@property (assign , nonatomic) mlViewEmotionInput *emotionInput;
@property (assign , nonatomic) UITextField *cmtInput;

@end

@implementation photoCommentViewController

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    UIView *cmtForm = [[UIView alloc] initWithFrame:CGRectMake(0, 0, 320, 40)];
    cmtForm.backgroundColor = [UIColor lightGrayColor];
    
    UIButton *voiceBtn = [UIButton buttonWithType:UIButtonTypeRoundedRect];
    voiceBtn.frame = CGRectMake(5, 5, 30, 30);
    voiceBtn.backgroundColor = [UIColor blueColor];
    [cmtForm addSubview:voiceBtn];
    
    self.cmtInput = [[UITextField alloc] initWithFrame:CGRectMake(40, 5, 220, 30)];
    self.cmtInput.backgroundColor = [UIColor whiteColor];
    self.cmtInput.delegate = self;
    [cmtForm addSubview:self.cmtInput];
    UIButton *cmtSubmit = [UIButton buttonWithType:UIButtonTypeRoundedRect];
    cmtSubmit.frame = CGRectMake(265, 5, 50, 30);
    cmtSubmit.backgroundColor = [UIColor blueColor];
    [cmtForm addSubview:cmtSubmit];
    
    self.emotionInput = [[mlViewEmotionInput alloc] init];
    
    
    
    [self.view addSubview:cmtForm];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
- (void)textFieldDidBeginEditing:(UITextField *)textField
{
    [self.emotionInput showInView:self.view];
}
- (void)textFieldDidEndEditing:(UITextField *)textField
{
    [self.emotionInput hide];
}
- (void)addEmotion:(id)sender
{
    [self.cmtInput insertText:@"aaa"];
}

@end
