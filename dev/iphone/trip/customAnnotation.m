//
//  customAnnotation.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-7.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "customAnnotation.h"

@implementation customAnnotation

@synthesize coordinate ,title,subtitle;

- (id)initWithCoordinate:(CLLocationCoordinate2D)coords
{
    if(self = [super init])
    {
        coordinate = coords;
    }
    return self;
}


@end
